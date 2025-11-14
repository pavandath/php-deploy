#!/usr/bin/env python3
import subprocess
import json
import sys
import os

def get_mig_instances():
    try:
        # Get external IPs
        cmd = [
            "gcloud", "compute", "instances", "list",
            "--filter=name:php-instance-*", 
            "--format=value(EXTERNAL_IP)"
        ]
        result = subprocess.run(cmd, capture_output=True, text=True, timeout=30)
        
        # Generate SSH key if it doesn't exist
        ssh_key_path = "/home/sa_100095016170461138857/.ssh/ansible_key"
        if not os.path.exists(ssh_key_path):
            os.makedirs("/home/sa_100095016170461138857/.ssh", exist_ok=True)
            subprocess.run([
                "ssh-keygen", "-t", "rsa", "-N", "", "-f", ssh_key_path
            ], capture_output=True, timeout=10)
            
            # Add public key to all PHP instances
            with open(ssh_key_path + ".pub", "r") as f:
                pub_key = f.read().strip()
            
            # Add key to project metadata (this will apply to all instances)
            subprocess.run([
                "gcloud", "compute", "project-info", "add-metadata",
                "--metadata=ssh-keys=ubuntu:" + pub_key
            ], capture_output=True, timeout=10)
        
        inventory = {
            "php_servers": {
                "hosts": [],
                "vars": {
                    "ansible_user": "ubuntu",
                    "ansible_become": "yes",
                    "ansible_ssh_private_key_file": ssh_key_path,
                    "ansible_ssh_common_args": "-o StrictHostKeyChecking=no -o UserKnownHostsFile=/dev/null"
                }
            }
        }
        
        # Add IPs to inventory
        ips = result.stdout.strip().split('\n')
        for ip in ips:
            if ip.strip():
                inventory["php_servers"]["hosts"].append(ip.strip())
        
        return inventory
        
    except Exception as e:
        print(f"ERROR: {e}", file=sys.stderr)
        return {"php_servers": {"hosts": []}}

if __name__ == "__main__":
    if len(sys.argv) == 2 and sys.argv[1] == "--list":
        print(json.dumps(get_mig_instances()))
    else:
        print(json.dumps({}))
