# Forlev D8

## Dev requirements
* Docker for mac [Official](https://www.docker.com/community-edition#/download) or eg. [Dinghy](https://github.com/codekitchen/dinghy)
* [Dory](https://github.com/FreedomBen/dory) for *.docker urls (optional, but you want it)

## Getting a database
First, have your public-key added to prod by asking Mads.
Then:
```bash
scripts/getdb.sh
```

## Container start
```bash
scripts/docker-reset.sh
```
