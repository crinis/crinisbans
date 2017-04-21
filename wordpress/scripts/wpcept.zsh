#!/bin/zsh
export DB_HOST=$(docker inspect -f '{{range .NetworkSettings.Networks}}{{.IPAddress}}{{end}}' db-testing)
export PROJECT_ROOT="$(pwd)"
export WP_ROOT="$(pwd)/volumes/html-testing"
crinisbans/vendor/bin/wpcept --config codeception.yml $@