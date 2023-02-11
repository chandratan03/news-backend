<?php

namespace App\Http\Controllers\News;

use App\Constants\HttpResponse;
use App\Helper\MyHelper;
use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\NewsCategory;
use App\Models\NewsSyncDate;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class NewsSyncController extends Controller
{


    public function sync()
    {
        $latestDate = NewsSyncDate::whereDate("created_at", Carbon::today())->get();
        if (count($latestDate) > 0) return MyHelper::customResponse([
            "dateSynced" => $latestDate
        ], "Already Synced");



        $this->syncGuardian();
        $this->syncNewsApi();
        $this->syncNYTimesApi();

        $syncDate = new NewsSyncDate();
        $syncDate->save();

        return MyHelper::customResponse(["syncDate" => $syncDate], "succes");
    }

    private function syncGuardian()
    {
        $GUARDIAN = "guardian";
        $guardianUrl = "https://content.guardianapis.com/search";
        $API_KEY = "bbe4fe68-5878-4964-b3d0-b79809715208";
        $showTags = "contributor";
        $showFields = "thumbnail";
        $sections = NewsCategory::all();
        $pageSize = 50;
        $todayDate = Carbon::now()->format("Y-m-d");
        $result = [];


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
                $result[] = $data;
                foreach ($data as $value) {
                    $news = new News();
                    $news["news_title"] = $value["webTitle"];
                    $news["news_category_id"] = $section["id"];
                    $news["news_publication_date"] = new DateTime($value["webPublicationDate"]);
                    $news["news_web_url"] = $value["webUrl"];
                    $news["news_source_data"] = $GUARDIAN;
                    $news["news_image_url"] = $value["fields"]["thumbnail"];
                    $news->save();
                    $result[] = $news;
                }
            }
        }

        return $result;
    }

    private function syncNewsApi()
    {
        $NEWS_API = "news api";
        $API_KEY = "29b80fdb71ac4e1ba098a7c448d16767";
        $country = "us";
        $categories = NewsCategory::all();
        $pageSize = 50;
        $result = [];

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
                    $news = new News();
                    $news["news_title"] = $value["title"];
                    $news["news_category_id"] = $category["id"];
                    $news["news_publication_date"] = new DateTime($value["publishedAt"]);
                    $news["news_web_url"] = $value["url"];
                    $news["news_source_data"] = $NEWS_API;
                    $news["news_image_url"] = $value["urlToImage"];
                    $news->save();
                    $result[] = $news;
                }
            }
            return $result;
        }

        return $result;
    }

    private function syncNYTimesApi()
    {
        $NY_TIMES = "NY_TIMES";
        $API_KEY = "mw9GwjbIG2ztA0nuwAcAuakjtzduBRAH";
        $sections = NewsCategory::all();
        $result = [];
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

                    $news = new News();
                    $news["news_title"] = $value["headline"]["main"];
                    $news["news_category_id"] = $section["id"];
                    $news["news_publication_date"] = new DateTime($value["pub_date"]);
                    $news["news_web_url"] = $value["web_url"];
                    $news["news_source_data"] = $NY_TIMES;
                    $news["news_image_url"] =  $image_url_ny_times . $multimedia[0]["url"];
                    $news->save();
                    $result[] = $news;
                }
            }
        }

        return $result;
    }
}
