This backend project serves as the API for this [frontend project] (https://github.com/chandratan03/news-frontend)
- https://content.guardianapis.com/search
- https://newsapi.org/v2/top-headlines
- https://api.nytimes.com/svc/search/v2/articlesearch.json


### Make Sure you install the docker in your machine. You can check the documentation how to install the docker from official website https://docs.docker.com/
### And also make sure the docker is running in your machine.
### If you are in windows, make sure to run the project using `WSL`. 

1. git clone https://github.com/chandratan03/news-backend.git
2. open up terminal and go to the project
3. Run the commands below
    -  `docker run --rm -u "$(id -u):$(id -g)" -v "$(pwd):/var/www/html" -w /var/www/html laravelsail/php82-composer:latest \composer install --ignore-platform-reqs`
    - `./vendor/bin/sail up -d`
    - `./vendor/bin/sail artisan migrate --seed`


4. Congratuliation!, you will be able to access the application via `http://localhost:3000/ `
5. Make sure the frontend's already running
6. Make sure to sync the news data first. By login with this credential: 
    - email: admin@email.com
    - password: admin123
7. Retrieve the token (bearer token) in the session storage
8. `localhost:8080/api/v1/news/sync` hit this API in order to sync the data from other sources, and make sure to use the token from the logged admin account (you can retrieve it from session storage). In order to hit the API, you can use POSTMAN, or curl.
