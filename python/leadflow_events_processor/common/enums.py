"""
Provides common enums.
"""

from enum import StrEnum


class TidbTable(StrEnum):
    """
    TiDB tables enumeration implementation.
    """

    SOURCE_ESTATES = 'source_estates'


class EventSource(StrEnum):
    """
    Supported events source enumeration implementation.
    """

    LEAD_FLOW_EVENTS = 'leadflow-events'


class EventType(StrEnum):
    """
    Supported events type enumeration implementation.
    """

    COMMENT_ADD = 'comment-add'
    COMMENT_REMOVE = 'comment-remove'
    CONTACT_ADD = 'contact-add'
    CONTACT_REMOVE = 'contact-remove'


class SourceEstatesField(StrEnum):
    """
    TiDB fields of `TidbTable.SOURCE_ESTATES` table.
    """

    APP_COMMENTED = 'app_commented'
    APP_CONTACTED = 'app_contacted'
