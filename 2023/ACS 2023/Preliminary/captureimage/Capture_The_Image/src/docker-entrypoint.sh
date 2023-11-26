#!/bin/bash
echo "Starting Challenge"
exec gunicorn 'wsgi:app' \
    --bind '0.0.0.0:22225' \
    --workers 5 \
