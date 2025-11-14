#!/bin/bash
# gcloud-ssh.sh - Minimal SSH wrapper

IP="$1"
shift

# Get instance name and zone from IP
INSTANCE_INFO=$(gcloud compute instances list --filter="networkInterfaces[0].accessConfigs[0].natIP=$IP" --format="value(NAME,ZONE)")
INSTANCE_NAME=$(echo "$INSTANCE_INFO" | cut -d' ' -f1)
ZONE=$(echo "$INSTANCE_INFO" | cut -d' ' -f2)

# Extract the actual command (last argument)
for arg in "$@"; do
    if [[ $arg != -* ]]; then
        COMMAND="$arg"
    fi
done

# Execute via gcloud compute ssh
gcloud compute ssh "$INSTANCE_NAME" --zone="$ZONE" --command="$COMMAND"
