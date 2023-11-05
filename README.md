# Symfony Test Quest

This repository is a customized version of Symfony Docker, specialized for a Symfony-based question-and-answer
examination application. It extends the foundational work of Symfony Docker to create a tailored testing environment
encapsulated within Docker.

## Introduction

The Symfony Test Quest project facilitates a simulated exam environment to interact with a database of questions and
answers, geared towards Symfony and web development learning and assessment. It is built upon Symfony and Docker,
ensuring a seamless and consistent development and deployment workflow.

## Initial Setup and Fixtures Loading

Begin with these steps to set up your environment:

1. [Install Docker Compose](https://docs.docker.com/compose/install/) (v2.10+), if not already installed.
2. Build fresh Docker images with `docker compose build --no-cache`.
3. Start the Docker environment using `docker compose up --pull --wait`.
4. Visit `https://localhost` and [accept the self-signed TLS certificate](https://stackoverflow.com/a/15076602/1352334).
5. Stop the Docker environment when done with `docker compose down --remove-orphans`.

To populate the database with initial data, run:

```bash
docker-compose exec php bin/console doctrine:fixtures:load
```

## Running the Exam Command

To initiate the exam simulation, use the following command:

```bash
docker-compose exec php bin/console app:exam
```

This command will trigger the exam interface in your console, allowing you to interact with the question-and-answer
functionality of the application.

## Contributions

This project is developed by [mariopastorlanchares](https://github.com/mariopastorlanchares) and is a fork of the
Symfony Docker project. Contributions to enhance and improve this project are warmly welcomed. Feel free to fork, send
pull requests, or open issues if you have suggestions or encounter any problems.

## Credits

Big shout-out to the crew over at Symfony Docker, especially [Kévin Dunglas](https://dunglas.fr), for the awesome
groundwork that's been laid down. This project couldn’t have kicked off without that stellar starting point.

A heap of thanks goes to the folks managing the question and answer pool at Certificationy.
The [`certificationy/certificationy`](https://github.com/certificationy/certificationy)
and [`certificationy/symfony-pack`](https://github.com/certificationy/symfony-pack) packs have been a goldmine for
setting up a solid Symfony quiz platform. Kudos to all those who chip in there!

And hey, I'm [mariopastorlanchares](https://github.com/mariopastorlanchares), tinkering away at this project. If you’re
keen to pitch in or just poke around the code, jump right in! The more, the merrier.
