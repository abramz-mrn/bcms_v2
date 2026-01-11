#!/usr/bin/env bash
set -e
docker compose -f infra/docker/compose/docker-compose.yml -f infra/docker/compose/docker-compose.prod.yml up -d
