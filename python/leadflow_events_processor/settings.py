"""
Provides base settings.
"""

from leadflow_events_processor.common.logging import LogLevel
from leadflow_events_processor.common.settings_models import (
    BaseEnvSettings,
    KafkaSettings,
    SentrySettings,
    TidbSettings,
)


class Settings(BaseEnvSettings):
    """
    Base common settings.
    """

    log_level: LogLevel = LogLevel.INFO
    sentry: SentrySettings = SentrySettings()
    kafka: KafkaSettings
    tidb: TidbSettings


base_settings = Settings()
