from fastapi import APIRouter, HTTPException
from services.yandex_direct import YandexDirectClient
from models.schemas import KeywordsRequest, ProxyRequest

router = APIRouter()
client = YandexDirectClient()

@router.post("/suggestions")
async def suggestions(payload: KeywordsRequest):
    try:
        # В Yandex Direct API Wordstat можно получить через report или специальные методы.
        # Здесь реализован базовый прокси для удобного расширения.
        return await client.proxy(
            "keywords",
            {
                "method": "get",
                "params": {
                    "SelectionCriteria": {"Phrases": payload.phrases},
                    "FieldNames": ["Id", "Keyword", "QualityEstimation"],
                },
            },
        )
    except Exception as exc:
        raise HTTPException(status_code=500, detail=str(exc))

@router.post("/proxy")
async def proxy(payload: ProxyRequest):
    try:
        return await client.proxy(payload.path, payload.body)
    except Exception as exc:
        raise HTTPException(status_code=500, detail=str(exc))
