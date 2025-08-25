## Project: `leadflow-events-processor`

### Table of contents

* [Development](#development)
  1. [Prepare the `.env` file](#1-prepare-the-env-file)
  2. [Create the local Python environment](#2-create-the-local-python-environment)
  3. [Useful commands](#3-useful-commands)
  4. [Run project with the `docker compose`](#4-run-project-with-the-docker-compose)
  5. [Environment variables](#5-environment-variables)

### Development

#### 1. Prepare the `.env` file

Create the `.env` file with required environment variables.
You can use the `.env.example` file as the base example.

#### 2. Create the local Python environment

* Install poetry if not installed ([documentation](https://python-poetry.org/docs/#installation))

```sh
pipx install poetry
```

* Create Python virtual environment

```
poetry config virtualenvs.create true
poetry env use python3.12
```

* Install project dependencies ([documentation](https://python-poetry.org/docs/basic-usage/#initialising-a-pre-existing-project))

```sh
poetry run poetry install
```

* Install the `pre-commit` hooks to run code quality and typing checks before every commit.

```sh
poetry run pre-commit install
```

#### 3. Useful commands

* Run [ruff](https://docs.astral.sh/ruff/) linter to perform the code quality check

```sh
poetry run ruff check .
```

* Run [ruff format](https://docs.astral.sh/ruff/formatter/) to perform the code formatting

```sh
poetry run ruff format .
```

* Run [mypy](https://mypy.readthedocs.io/) to perform the static type check

```sh
poetry run mypy .
```

* Run unit tests

```sh
poetry run pytest -v tests/unit_tests/
```

#### 4. Run project with the `docker compose`

* Up project:

```sh
docker compose up --build
```

* Check services logs:

```sh
docker compose logs -f events-processor
```

* Check data in Kafka with the Kafka-UI:

```
http://127.0.0.1:9080
```

* Shut down the project: stop and remove containers, networks, volumes:

```sh
docker compose down -v
```

#### 5. Environment variables

##### Docker image build arguments

| Variable     | Required | Description                                                                                                                                          | References |
|--------------|:--------:|------------------------------------------------------------------------------------------------------------------------------------------------------|------------|
| `GIT_COMMIT` |    No    | The last git commit hash.                                                                                                                            | —          |
| `DEBUG`      |    No    | Defines the dependencies to be added to the docker image during the build. Values: `true` or `false`. If `false` - only main dependencies are added. | —          |

##### Common environment variables

| Variable                            | Required | Description                                                                                                                                           | References                                                                                                    |
|-------------------------------------|:--------:|-------------------------------------------------------------------------------------------------------------------------------------------------------|---------------------------------------------------------------------------------------------------------------|
| `LOG_LEVEL`                         |    No    | The logging level. Default: `INFO`.                                                                                                                   | —                                                                                                             |
| `SENTRY__DSN`                       |    No    | Sentry DSN to send exceptions to. Format: `https://<token>@<host>/<project_id>`.                                                                      |                                                                                                               |
| `SENTRY__TRACES_SAMPLE_RATE`        |    No    | The volume of transactions sent to Sentry. From `0` to `1`. Default: `null`.                                                                          | [#1](https://docs.sentry.io/platforms/python/configuration/sampling/#configuring-the-transaction-sample-rate) |
| `KAFKA__VERSION`                    |   Yes    | Kafka cluster version.                                                                                                                                | —                                                                                                             |
| `KAFKA__BOOTSTRAP_SERVERS`          |   Yes    | List of Kafka nodes in the format `<host>:<port>`, separated by commas.                                                                               | [#1](https://kafka.apache.org/documentation/#connectconfigs_bootstrap.servers)                                |
| `TIDB__URL`                         |   Yes    | Database DSN to get events from. Format: `mysql://<username>:<password>@<host>:<port>/<database>`.                                                    | —                                                                                                             |

##### Service-specific environment variables

* `events-processor` service

| Variable                                  | Required | Description                                                                                                                                              | References                                                    |
|-------------------------------------------|:--------:|----------------------------------------------------------------------------------------------------------------------------------------------------------|---------------------------------------------------------------|
| `EVENTS_PROCESSOR__KAFKA_TOPIC`           |   Yes    | Kafka topic that should be used to consume events.                                                                                                       | —                                                             |
| `EVENTS_PROCESSOR__KAFKA_TOPIC_CONFIG`    |    No    | Kafka topic configuration parameters as json object (to create topic if it does not exist). For example: `{"partitions": 4, "retention.ms": 259200000}`. | [#1](https://kafka.apache.org/documentation/#topicconfigs)    |
| `EVENTS_PROCESSOR__KAFKA_CONSUMER_CONFIG` |    No    | The Kafka consumer configuration parameters to override defaults as json object. For example: `{"auto.offset.reset": "earliest"}`.                       | [#1](https://kafka.apache.org/documentation/#consumerconfigs) |
| `EVENTS_PROCESSOR__BATCH_SIZE`            |    No    | The maximum number of messages to consume from Kafka and process at a time. Default: `100`.                                                              | —                                                             |
| `EVENTS_PROCESSOR__BATCH_TIMEOUT`         |    No    | The maximum time in seconds to block waiting for messages from Kafka. Default: `1` second.                                                               | —                                                             |
