"""
Provides common settings models.
"""

from pydantic import (
    BaseModel,
    Field,
    HttpUrl,
    MySQLDsn,
)
from pydantic_settings import (
    BaseSettings,
    SettingsConfigDict,
)


class BaseEnvSettings(BaseSettings):
    """
    Base environment variables settings.
    """

    model_config = SettingsConfigDict(
        env_nested_delimiter='__',
        frozen=True,
    )


class SentrySettings(BaseModel):
    """
    Sentry settings.
    """

    dsn: HttpUrl | None = None
    traces_sample_rate: float | None = Field(gt=0, le=1.0, default=None)


class KafkaSettings(BaseModel):
    """
    Base Kafka settings.
    """

    version: str
    bootstrap_servers: str


class TidbSettings(BaseModel):
    """
    Base TiDB database settings.
    """

    url: MySQLDsn
