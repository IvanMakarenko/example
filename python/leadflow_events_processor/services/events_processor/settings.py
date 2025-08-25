"""
Provides events processor service settings.
"""

from pydantic_settings import (
    BaseSettings,
    SettingsConfigDict,
)

from leadflow_events_processor.common.constants import PROJECT_NAME
from leadflow_events_processor.common.typing import AllowJson
from leadflow_events_processor.external_services.kafka.config_models import (
    KAFKA_CONSUMER_GROUP_ID_TEMPLATE,
    KafkaConsumerConfig,
    KafkaTopicConfig,
)
from leadflow_events_processor.settings import base_settings


SERVICE_NAME = 'events-processor'
SERVICE_NAME_ENV_PREFIX = SERVICE_NAME.replace('-', '_')


class EventsProcessorKafkaConsumerConfig(KafkaConsumerConfig):
    """
    Events processor kafka consumer config.
    """

    bootstrap_servers: str = base_settings.kafka.bootstrap_servers
    group_id: str = KAFKA_CONSUMER_GROUP_ID_TEMPLATE.substitute(project_name=PROJECT_NAME, service_name=SERVICE_NAME)


class EventsProcessorSettings(BaseSettings):
    """
    Events processor service settings.
    """

    model_config = SettingsConfigDict(
        env_prefix=f'{SERVICE_NAME_ENV_PREFIX}__',
        env_nested_delimiter='__',
        frozen=True,
    )

    kafka_topic: str
    kafka_topic_config: AllowJson[KafkaTopicConfig] = KafkaTopicConfig()
    kafka_consumer_config: AllowJson[EventsProcessorKafkaConsumerConfig] = EventsProcessorKafkaConsumerConfig()
    batch_size: int = 1
    batch_timeout: int = 1


events_processor_settings = EventsProcessorSettings()
