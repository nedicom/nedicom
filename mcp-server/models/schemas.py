from typing import Dict, List, Optional
from pydantic import BaseModel, Field

class CampaignsRequest(BaseModel):
    selection_criteria: Optional[Dict[str, object]] = Field(default_factory=dict)

class AdsRequest(BaseModel):
    campaign_ids: List[int]
    selection_criteria: Optional[Dict[str, object]] = Field(default_factory=dict)

class KeywordsRequest(BaseModel):
    phrases: List[str]

class ReportRequest(BaseModel):
    report_body: Dict[str, object]

class ProxyRequest(BaseModel):
    path: str
    body: Dict[str, object]
