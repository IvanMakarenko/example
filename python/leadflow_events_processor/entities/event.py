"""
Base event enteties.
"""

from pydantic import BaseModel, ConfigDict, Field

from leadflow_events_processor.common.enums import EventSource, EventType


class EventData(BaseModel):
    """
    Event data entity which describes something happened with a listing.
    """

    id: int
    user_id: str = Field(alias='userId')
    company_id: str = Field(alias='companyId')
    listing_id: int = Field(alias='listingId')

    model_config = ConfigDict(populate_by_name=True)


class Event(BaseModel):
    """
    Event data entity which describes something happened with a listing.
    """

    source: EventSource
    type: EventType
    data: EventData
