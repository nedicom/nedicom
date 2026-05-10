from fastapi import APIRouter, HTTPException
from services.yandex_direct import YandexDirectClient
from models.schemas import CampaignsRequest, AdsRequest, KeywordsRequest, ReportRequest, ProxyRequest

router = APIRouter()
client = YandexDirectClient()

@router.post("/campaigns")
async def campaigns(payload: CampaignsRequest):
    try:
        return await client.list_campaigns(payload.selection_criteria)
    except Exception as exc:
        raise HTTPException(status_code=500, detail=str(exc))

@router.post("/ads")
async def ads(payload: AdsRequest):
    try:
        return await client.list_ads(payload.campaign_ids, payload.selection_criteria)
    except Exception as exc:
        raise HTTPException(status_code=500, detail=str(exc))

@router.post("/keywords")
async def keywords(payload: KeywordsRequest):
    try:
        return await client.get_keywords(payload.phrases)
    except Exception as exc:
        raise HTTPException(status_code=500, detail=str(exc))

@router.post("/reports")
async def reports(payload: ReportRequest):
    try:
        return await client.create_report(payload.report_body)
    except Exception as exc:
        raise HTTPException(status_code=500, detail=str(exc))

@router.post("/proxy")
async def proxy(payload: ProxyRequest):
    try:
        return await client.proxy(payload.path, payload.body)
    except Exception as exc:
        raise HTTPException(status_code=500, detail=str(exc))

@router.post("/execute")
async def execute(payload: ProxyRequest):
    try:
        return await client.proxy(payload.path, payload.body)
    except Exception as exc:
        raise HTTPException(status_code=500, detail=str(exc))
