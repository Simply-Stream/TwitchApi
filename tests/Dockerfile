FROM homebrew/brew AS download_twitch_cli

RUN brew update-reset && brew install twitchdev/twitch/twitch-cli

FROM ubuntu:23.10
ARG TWITCH_CLI_VERSION=1.1.21

COPY --chmod=777 --chown=root:root --from=download_twitch_cli /home/linuxbrew/.linuxbrew/Cellar/twitch-cli/${TWITCH_CLI_VERSION}/bin/twitch /bin/twitch

CMD ["/bin/twitch", "mock-api", "start"]
EXPOSE 8080
