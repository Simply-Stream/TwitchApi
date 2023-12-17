# golang:1.21.5-alpine will break to build, we'll stay on 1.21.4 for now
FROM golang:1.21-alpine AS builder

RUN apk add --no-cache git build-base

WORKDIR /app

RUN git clone -b fix/mock-api https://github.com/Simply-Stream/twitch-cli.git

RUN cd twitch-cli && go get -d -v && CGO_CFLAGS="-D_LARGEFILE64_SOURCE" go build

FROM alpine:latest

WORKDIR /app

RUN apk add --no-cache tzdata

COPY ./tests/eventCache.db /root/.config/twitch-cli/eventCache.db
COPY --from=builder /app/twitch-cli/twitch-cli /app/twitch-cli

ENTRYPOINT ["/app/twitch-cli", "mock-api", "start"]
EXPOSE 8080
