from pathlib import Path
from dotenv import load_dotenv
import os

BASE_DIR = Path(__file__).resolve().parent

# Ищем .env в текущей папке mcp-server или в родительской папке проекта
env_path = None
for candidate in [BASE_DIR / ".env", BASE_DIR.parent / ".env"]:
    if candidate.exists():
        env_path = candidate
        break

if env_path is None:
    raise RuntimeError(".env file not found in mcp-server or its parent directory")

load_dotenv(env_path)

YANDEX_DIRECT_TOKEN = os.getenv("YANDEX_DIRECT_TOKEN", "")
YANDEX_CLIENT_LOGIN = os.getenv("YANDEX_CLIENT_LOGIN", "")
YANDEX_API_ENDPOINT = os.getenv("YANDEX_API_ENDPOINT", "https://api.direct.yandex.com/json/v5")

if not YANDEX_DIRECT_TOKEN:
    raise RuntimeError("YANDEX_DIRECT_TOKEN is required in .env file")
