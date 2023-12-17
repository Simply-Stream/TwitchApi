# golang:1.21.5-alpine will break to build, we'll stay on 1.21.4 for now
FROM golang:1.21.4-alpine AS builder

RUN apk update && apk add --no-cache git build-base

WORKDIR /app

RUN git clone https://github.com/Simply-Stream/twitch-cli.git

RUN cd twitch-cli && go get -d -v && go build

FROM alpine:latest

WORKDIR /app

COPY --from=builder /app/twitch-cli/twitch-cli /app/twitch-cli

ENTRYPOINT ["/app/twitch-cli", "mock-api", "start", "-p", "8000"]
EXPOSE 8000
