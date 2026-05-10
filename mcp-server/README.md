# MCP Server for Yandex Direct / Wordstat API

Этот проект содержит локальный MCP-сервер на Python для работы с API Яндекс Директа и Wordstat.

## Возможности

- Список кампаний
- Список объявлений
- Генерация отчётов
- Получение подсказок ключевых слов
- Универсальный прокси для запросов в API Яндекс Директа

## Быстрый старт

1. Перейдите в папку проекта:

```bash
cd mcp-server
```

2. Создайте виртуальное окружение и установите зависимости:

```bash
python -m venv .venv
.\.venv\Scripts\activate
python -m pip install -U pip
python -m pip install -r requirements.txt
```

4. Создайте файл `.env` на основе `.env.example` и заполните ваши ключи.
   Сервер автоматически ищет `.env` в текущей папке `mcp-server` и в родительской папке проекта `nedicom`.

```env
YANDEX_DIRECT_TOKEN=ваш_токен
YANDEX_CLIENT_LOGIN=логин_клиента
```

4. Запустите сервер:

```bash
uvicorn app:app --reload --host 127.0.0.1 --port 8000
```

5. Откройте документацию OpenAPI:

`http://127.0.0.1:8000/docs`

## Примеры

- `POST /direct/campaigns` — список кампаний
- `POST /direct/ads` — список объявлений
- `POST /direct/keywords` — поиск ключевых слов
- `POST /direct/reports` — создание отчёта
- `POST /direct/proxy` — произвольный запрос к API Директа
- `POST /direct/execute` — тот же функционал, что и `/direct/proxy`
- `POST /wordstat/suggestions` — подсказки ключевых слов через Wordstat
- `POST /wordstat/proxy` — произвольный запрос к Wordstat API через MCP

## Claude → MCP → Яндекс Директ

Чтобы использовать MCP-сервер как промежуточный слой между Claude и Яндекс API, отправляйте запросы на локальный сервер с JSON-телом.

Пример запроса для получения подсказок ключевых слов:

```bash
curl -X POST http://127.0.0.1:8000/wordstat/suggestions \
  -H "Content-Type: application/json" \
  -d '{"phrases": ["клиника", "медцентр"]}'
```

Пример произвольного запроса из Claude к Яндекс Директ через MCP:

```bash
curl -X POST http://127.0.0.1:8000/direct/proxy \
  -H "Content-Type: application/json" \
  -d '{
    "path": "keywords",
    "body": {
      "method": "get",
      "params": {
        "SelectionCriteria": {"Phrases": ["клиника"], "Page": {"Limit": 10}},
        "FieldNames": ["Id", "Keyword", "QualityEstimation"]
      }
    }
  }'
```

Если Claude может отправлять HTTP-запросы, этот сервер выступает как шлюз между моделью и API Яндекс Директа / Wordstat.
