from fastapi import FastAPI
from routers import direct, wordstat

app = FastAPI(
    title="Yandex Direct MCP Server",
    description="Локальный MCP-сервер для Яндекс Директа и Wordstat API",
    version="0.1.0",
)

app.include_router(direct.router, prefix="/direct", tags=["Яндекс Директ"])
app.include_router(wordstat.router, prefix="/wordstat", tags=["Wordstat"])

@app.get("/")
def health_check():
    return {"status": "ok", "service": "Yandex Direct MCP Server"}
