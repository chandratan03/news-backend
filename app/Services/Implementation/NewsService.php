<?php

namespace App\Services\Implementation;


use App\Repositories\ContributorRepositoryInterface;
use App\Repositories\NewsCategoryRepositoryInterface;
use App\Repositories\NewsContributorRepositoryInterface;
use App\Repositories\NewsRepositoryInterface;
use App\Repositories\NewsSyncDateRepositoryInterface;
use App\Repositories\SourceRepositoryInterface;
use App\Services\INewsService;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Http;

class NewsService implements INewsService
{
    private $newsRepository;
    private $newsCategoryRepository;
    private $newsSyncDateRepository;
    private $contributorRepository;
    private $newsContributorRepository;
    private $sourceRepository;

    public function __construct(
        NewsRepositoryInterface $newsRepository,
        NewsCategoryRepositoryInterface $newsCategoryRepository,
        NewsSyncDateRepositoryInterface $newsSyncDateRepository,
        ContributorRepositoryInterface $contributorRepository,
        NewsContributorRepositoryInterface $newsContributorRepository,
        SourceRepositoryInterface $sourceRepository,
    ) {
        $this->newsRepository = $newsRepository;
        $this->newsCategoryRepository = $newsCategoryRepository;
        $this->newsSyncDateRepository = $newsSyncDateRepository;
        $this->contributorRepository = $contributorRepository;
        $this->newsContributorRepository = $newsContributorRepository;
        $this->sourceRepository = $sourceRepository;
    }

    public function sync()
    {
        $latestDate = $this->newsSyncDateRepository->findByCreatedAt(Carbon::today());
        if (!$latestDate || count($latestDate) > 0) {
            return [
                "data" => ["dateSynced" => $latestDate],
                "message" => "Already Synced"
            ];
        }
        $this->syncGuardian();
        $this->syncNewsApi();
        $this->syncNYTimesApi();
        $syncDate = $this->newsSyncDateRepository->create([]);

        return [
            "data" => ["syncDate" => $syncDate],
            "message" => "Success",
        ];
    }

    private function syncGuardian()
    {
        $sourceId = $this->sourceRepository->findByWhere([["source_name", "Guardian"]])->first()["id"];
        $guardianUrl = "https://content.guardianapis.com/search";
        $API_KEY = env("GUARDIAN_API_KEY");
        if(!$API_KEY) return;

        $showTags = "contributor";
        $showFields = "thumbnail";
        $sections = $this->newsCategoryRepository->all();
        $pageSize = 50;
        $todayDate = Carbon::now()->format("Y-m-d");

        foreach ($sections as $section) {
            $guardianGetParams = [
                "show-tags" => $showTags,
                "api-key" => $API_KEY,
                "page-size" => $pageSize,
                "section" => $section["news_category_name"],
                "from-date" => $todayDate,
                "show-fields" => $showFields,
            ];

            $response = Http::get($guardianUrl, $guardianGetParams);
            if ($response->successful()) {
                $data = $response["response"]["results"];
                foreach ($data as $value) {
                    $tags = $value["tags"];
                    $contributors = [];

                    foreach ($tags as $tag) {
                        $contributorCollection = $this->contributorRepository->findByWhere([["contributor_name", $tag["webTitle"]]]);

                        if (count($contributorCollection) > 0) {
                            $contributors[] = $contributorCollection->first();
                        } else {
                            $contributors[] = $this->contributorRepository->create([
                                "contributor_name" =>  $tag["webTitle"],
                            ]);
                        }
                    }

                    $news = $this->newsRepository->create([
                        "news_title" => $value["webTitle"],
                        "news_category_id" =>  $section["id"],
                        "news_publication_date" => new DateTime($value["webPublicationDate"]),
                        "news_web_url" =>  $value["webUrl"],
                        "news_source_id" =>  $sourceId,
                        "news_image_url" =>  $value["fields"]["thumbnail"],
                    ]);
                    foreach ($contributors as $contributor) {
                        $this->newsContributorRepository->create([
                            "contributor_id" => $contributor["id"],
                            "news_id" => $news["id"],
                        ]);
                    }
                }
            }
        }
    }

    private function syncNewsApi()
    {
        $sourceId = $this->sourceRepository->findByWhere([["source_name", "News API"]])->first()["id"];
        $API_KEY = env("NEWS_API_API_KEY");
        if(!$API_KEY) return;

        $country = "us";
        $categories = $this->newsCategoryRepository->all();
        $pageSize = 50;

        foreach ($categories as $category) {
            $newsApiParams = [
                "country" => $country,
                "apiKey" => $API_KEY,
                "page-size" => $pageSize,
                "category" => $category["news_category_name"],
            ];

            $newsApiUrl = "https://newsapi.org/v2/top-headlines";
            $response = Http::get($newsApiUrl, $newsApiParams);

            if ($response->successful()) {
                $data = $response["articles"];
                $result[] = $data;
                foreach ($data as $value) {
                    if ($value["urlToImage"] === null) continue;
                    $news = $this->newsRepository->create([
                        "news_title" => $value["title"],
                        "news_category_id" => $category["id"],
                        "news_publication_date" => new DateTime($value["publishedAt"]),
                        "news_web_url" => $value["url"],
                        "news_source_id" => $sourceId,
                        "news_image_url" => $value["urlToImage"],
                    ]);
                    $contributor = null;
                    $value["author"] = !empty($value["author"])  ? $value["author"] : "unknown";
                    $contributorCollection = $this->contributorRepository->findByWhere([["contributor_name", $value["author"]]]);

                    if (count($contributorCollection) > 0) {
                        $contributor = $contributorCollection->first();
                    } else {
                        $contributor = $this->contributorRepository->create([
                            "contributor_name" =>  $value["author"],
                        ]);
                    }



                    $this->newsContributorRepository->create([
                        "contributor_id" => $contributor["id"],
                        "news_id" => $news["id"],
                    ]);
                }
            }
        }
    }

    private function syncNYTimesApi()
    {
        $sourceId = $this->sourceRepository->findByWhere([["source_name", "NY Times"]])->first()["id"];
        $API_KEY = env("NY_TIMES_API_KEY");
        if(!$API_KEY) return;

        $sections = $this->newsCategoryRepository->all();
        $image_url_ny_times = "https://static01.nyt.com/";
        $nyTimesApiUrl = "https://api.nytimes.com/svc/search/v2/articlesearch.json";
        $todayDate = Carbon::now()->format("Y-m-d");

        foreach ($sections as $section) {
            $nyTimesApiParams = [
                "section_name" => $section["news_category_name"],
                "api-key" => $API_KEY,
                "pub_date" => $todayDate,
            ];

            $response = Http::get($nyTimesApiUrl, $nyTimesApiParams);
            if ($response->successful()) {
                $data = $response["response"]["docs"];
                foreach ($data as $value) {
                    $multimedia = array_filter($value["multimedia"], function ($media) {
                        return $media["type"] == "image";
                    });
                    if (count($multimedia) === 0) continue;

                    $persons = $value["byline"]["person"];
                    $contributors = [];
                    if ($persons && count($persons) > 0) {
                        foreach ($persons as $person) {
                            $name = "{$person["firstname"]}";
                            $name = $name . (array_key_exists("middlename", $person) && !empty($person["middlename"]) ? " {$person['middleName']} " : "");
                            $name = $name . (array_key_exists("lastname", $person) && !empty($person["lastname"]) ? " {$person['lastname']} " : "");

                            $contributorCollection = $this->contributorRepository->findByWhere([["contributor_name", $name]]);

                            if (count($contributorCollection) > 0) {
                                $contributors[] = $contributorCollection->first();
                            } else {
                                $contributors[] = $this->contributorRepository->create([
                                    "contributor_name" =>  $name,
                                ]);
                            }
                        }
                    }
                    if ($value["byline"]["organization"]) {
                        $contributor = $value["byline"]["organization"];
                        $contributorCollection = $this->contributorRepository->findByWhere([["contributor_name", $contributor]]);

                        if (count($contributorCollection) > 0) {
                            $contributors[] = $contributorCollection->first();
                        } else {
                            $contributors[] = $this->contributorRepository->create([
                                "contributor_name" =>  $contributor,
                            ]);
                        }
                    }


                    $this->newsRepository->create([
                        "news_title" => $value["headline"]["main"],
                        "news_category_id" => $section["id"],
                        "news_publication_date" => new DateTime($value["pub_date"]),
                        "news_web_url" => $value["web_url"],
                        "news_source_id" => $sourceId,
                        "news_image_url" =>  $image_url_ny_times . $multimedia[0]["url"],
                    ]);
                }
            }
        }
    }


    public function paginate($pageSize, $data)
    {
        $params = $this->searchParams($data);
        $params = $this->filterByAuthor($params, $data["author"]);

        $result = $this->newsRepository->paginationWithWhere($params, $data["author"], $pageSize);
        foreach ($result as $news) {
            foreach ($news->newsContributors as $newsContributor) {
                $newsContributor->contributor;
            }
            $news->source;
            $news->newsCategory;
        }

        return $result;
    }

    private function filterByAuthor($params, $author)
    {
        if ($author) {
            $newsContributors = $this->newsContributorRepository->findByWhere([
                ["contributor_id", $author]
            ]);
            $newsIds = [];
            foreach ($newsContributors as $newsContributor) {
                $newsIds[] = $newsContributor["news_id"];
            }
            $params[] = ["id", $newsIds];
        }
        return $params;
    }

    private function searchParams($data)
    {
        $query = $data["query"];
        $date = $data["date"];
        $category = $data["category"];
        $source = $data["source"];

        $params = [];

        if ($date) {
            $params[]  = ["news_publication_date", $date];
        }
        if ($category) {
            $params[]  = ["news_category_id", $category];
        }
        if ($source) {
            $params[]  = ["news_source_id", $source];
        }

        if ($query) {
            $params[] = ["news_title", "LIKE", "%$query%"];
        }

        return $params;
    }
}
