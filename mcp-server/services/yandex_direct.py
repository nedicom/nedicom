from typing import Any, Dict, List, Optional
import httpx
from config import YANDEX_DIRECT_TOKEN, YANDEX_CLIENT_LOGIN, YANDEX_API_ENDPOINT

class YandexDirectClient:
    def __init__(self):
        self.endpoint = YANDEX_API_ENDPOINT
        self.headers = {
            "Authorization": f"Bearer {YANDEX_DIRECT_TOKEN}",
            "Accept-Language": "ru",
            "Content-Type": "application/json",
        }
        if YANDEX_CLIENT_LOGIN:
            self.headers["Client-Login"] = YANDEX_CLIENT_LOGIN

    async def request(self, path: str, payload: Dict[str, Any]) -> Dict[str, Any]:
        url = f"{self.endpoint}/{path}"
        async with httpx.AsyncClient() as client:
            response = await client.post(url, json=payload, headers=self.headers, timeout=60.0)
            response.raise_for_status()
            return response.json()

    async def list_campaigns(self, selection_criteria: Optional[Dict[str, Any]] = None) -> Dict[str, Any]:
        payload = {"method": "get", "params": {"SelectionCriteria": selection_criteria or {}, "FieldNames": ["Id", "Name", "Status", "StartDate", "EndDate"]}}
        return await self.request("campaigns", payload)

    async def list_ads(self, campaign_ids: List[int], selection_criteria: Optional[Dict[str, Any]] = None) -> Dict[str, Any]:
        payload = {
            "method": "get",
            "params": {
                "SelectionCriteria": {"CampaignIds": campaign_ids, **(selection_criteria or {})},
                "FieldNames": ["Id", "CampaignId", "Status", "Headline", "Description", "Href"],
            },
        }
        return await self.request("ads", payload)

    async def get_keywords(self, phrases: List[str]) -> Dict[str, Any]:
        payload = {"method": "get", "params": {"SelectionCriteria": {"Phrases": phrases}, "FieldNames": ["Id", "Keyword", "QualityEstimation"]}}
        return await self.request("keywords", payload)

    async def create_report(self, report_body: Dict[str, Any]) -> Dict[str, Any]:
        return await self.request("reports", report_body)

    async def proxy(self, path: str, body: Dict[str, Any]) -> Dict[str, Any]:
        return await self.request(path, body)
