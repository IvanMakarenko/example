"""
Provides common typing classes.
"""

from typing import (
    TypeVar,
    Union,
)

from pydantic import Json


T = TypeVar('T')
AllowJson = Union[T, Json[T]]
