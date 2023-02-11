<?php

namespace App\Services;

use App\Helper\MyHelper;
use App\Models\News;
use App\Models\NewsCategory;
use App\Repositories\Eloquent\NewsRepository;
use App\Repositories\NewsCategoryRepositoryInterface;
use App\Repositories\NewsRepositoryInterface;
use App\Repositories\NewsSyncDateRepositoryInterface;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class NewsService
{
    private $newsRepository;
    private $newsCategoryRepository;
    private $newsSyncDateRepository;
    public function __construct(
        NewsRepositoryInterface $newsRepository,
        NewsCategoryRepositoryInterface $newsCategoryRepository,
        NewsSyncDateRepositoryInterface $newsSyncDateRepository
    ) {
        $this->newsRepository = $newsRepository;
        $this->newsCategoryRepository = $newsCategoryRepository;
        $this->newsSyncDateRepository = $newsSyncDateRepository;
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
        $GUARDIAN = "guardian";
        $guardianUrl = "https://content.guardianapis.com/search";
        $API_KEY = "bbe4fe68-5878-4964-b3d0-b79809715208";
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
                    $res = $this->newsRepository->create([
                        "news_title" => $value["webTitle"],
                        "news_category_id" =>  $section["id"],
                        "news_publication_date" => new DateTime($value["webPublicationDate"]),
                        "news_web_url" =>  $value["webUrl"],
                        "news_source_data" =>  $GUARDIAN,
                        "news_image_url" =>  $value["fields"]["thumbnail"],
                    ]);
                }
            }
        }
    }

    private function syncNewsApi()
    {
        $NEWS_API = "news api";
        $API_KEY = "29b80fdb71ac4e1ba098a7c448d16767";
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
                    $this->newsRepository->create([
                        "news_title" => $value["title"],
                        "news_category_id" => $category["id"],
                        "news_publication_date" => new DateTime($value["publishedAt"]),
                        "news_web_url" => $value["url"],
                        "news_source_data" => $NEWS_API,
                        "news_image_url" => $value["urlToImage"],
                    ]);
                }
            }
        }
    }

    private function syncNYTimesApi()
    {
        $NY_TIMES = "NY_TIMES";
        $API_KEY = "mw9GwjbIG2ztA0nuwAcAuakjtzduBRAH";
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
                    $this->newsRepository->create([
                        "news_title" => $value["headline"]["main"],
                        "news_category_id" => $section["id"],
                        "news_publication_date" => new DateTime($value["pub_date"]),
                        "news_web_url" => $value["web_url"],
                        "news_source_data" => $NY_TIMES,
                        "news_image_url" =>  $image_url_ny_times . $multimedia[0]["url"],
                    ]);
                }
            }
        }
    }
}
