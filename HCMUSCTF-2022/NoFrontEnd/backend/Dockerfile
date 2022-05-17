# Use the offical Golang image to create a build artifact.
# This is based on Debian and sets the GOPATH to /go.
# https://hub.docker.com/_/golang

FROM golang:1.16 as builder

# Copy local code to the container image.
WORKDIR /app
COPY . .

LABEL maintainer="Dora"

RUN mkdir -p /go/src/challenge
COPY . /go/src/challenge
WORKDIR /go/src/challenge/cmd

# Build the command inside the container.
RUN CGO_ENABLED=0 GOOS=linux go build -a -v -installsuffix cgo -o backend .

# Use a Docker multi-stage build to create a lean production image.
# https://docs.docker.com/develop/develop-images/multistage-build/#use-multi-stage-builds
FROM alpine
RUN apk add --no-cache ca-certificates

# Copy the binary to the production image from the builder stage.
COPY --from=builder /go/src/challenge/cmd/backend /backend

# Run the web service on container startup.
CMD ["/backend"]