#!/usr/bin/env python3
import subprocess
import json
import sys

def get_mig_instances():
    try:
        cmd = [
            "gcloud", "compute", "instance-groups", "list-instances", 
            "php-mig", "--region=us-central1", "--format=json"
        ]
        result = subprocess.run(cmd, capture_output=True, text=True, timeout=30)
        
        if result.returncode != 0:
            return {"php_servers": {"hosts": []}}
        
        instances = json.loads(result.stdout)
        
        inventory = {
            "php_servers": {
                "hosts": [],
                "vars": {
                    "ansible_user": "sa_107639644271753149281",
                    "ansible_become": "yes",
                    "ansible_ssh_common_args": "-o StrictHostKeyChecking=no -o UserKnownHostsFile=/dev/null",
                    "ansible_ssh_private_key_file": "~/.ssh/google_compute_engine"
                }
            },
            "all": {
                "children": ["php_servers"]
            }
        }
        
        for instance in instances:
            instance_name = instance["instance"].split("/")[-1]
            
            # Get the external IP for this instance
            ip_cmd = [
                "gcloud", "compute", "instances", "describe", instance_name,
                "--zone=us-central1-f", "--format=json"
            ]
            ip_result = subprocess.run(ip_cmd, capture_output=True, text=True, timeout=30)
            
            if ip_result.returncode == 0:
                instance_details = json.loads(ip_result.stdout)
                # Extract external IP
                for interface in instance_details.get("networkInterfaces", []):
                    for config in interface.get("accessConfigs", []):
                        if "natIP" in config and config["natIP"]:
                            inventory["php_servers"]["hosts"].append(config["natIP"])
                            break
        
        return inventory
        
    except Exception as e:
        print(f"Error: {e}", file=sys.stderr)
        return {"php_servers": {"hosts": []}}

if __name__ == "__main__":
    if len(sys.argv) == 2 and sys.argv[1] == "--list":
        print(json.dumps(get_mig_instances()))
    elif len(sys.argv) == 2 and sys.argv[1] == "--host":
        print(json.dumps({}))
    else:
        print(json.dumps({}))
