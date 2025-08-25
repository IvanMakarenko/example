"""
Provides constants for tests.
"""

ENVIRONMENT_VARIABLES_DEFAULT = {
    'KAFKA__VERSION': '3.2.0',
    'KAFKA__BOOTSTRAP_SERVERS': 'kafka-0:9092',
    'TIDB__URL': 'mysql://test:test@tidb:4000/shketiam',
    'EVENTS_PROCESSOR__KAFKA_TOPIC': 'event-hub',
}
