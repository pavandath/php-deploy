#!/usr/bin/env python3
import subprocess
import json
import sys

def get_mig_instances():
    try:
        # Get instances with all details
        cmd = [
            "gcloud", "compute", "instances", "list",
            "--filter=name:php-instance-*", 
            "--format=json"
        ]
        result = subprocess.run(cmd, capture_output=True, text=True, timeout=30)
        
        instances = json.loads(result.stdout)
        
        inventory = {
            "php_servers": {
                "hosts": [],
                "vars": {
                    "ansible_user": "ubuntu",
                    "ansible_become": "yes",
                    "ansible_connection": "local"  # This tells Ansible to run locally and use gcloud
                }
            }
        }
        
        # Add instance names instead of IPs
        for instance in instances:
            inventory["php_servers"]["hosts"].append(instance["name"])
        
        return inventory
        
    except Exception as e:
        return {"php_servers": {"hosts": []}}

if __name__ == "__main__":
    if len(sys.argv) == 2 and sys.argv[1] == "--list":
        print(json.dumps(get_mig_instances()))
    else:
        print(json.dumps({}))
