#!/usr/bin/env python3
import subprocess
import json
import sys
import os

def setup_ssh_keys():
    """Automatically set up SSH keys for all PHP instances"""
    try:
        # Generate SSH key if not exists
        ssh_key_path = os.path.expanduser("~/.ssh/ansible_key")
        if not os.path.exists(ssh_key_path):
            os.makedirs(os.path.dirname(ssh_key_path), exist_ok=True)
            subprocess.run([
                "ssh-keygen", "-t", "rsa", "-N", "", "-f", ssh_key_path
            ], capture_output=True, check=True)
            print("Generated SSH key", file=sys.stderr)
        
        # Read public key
        with open(ssh_key_path + ".pub", "r") as f:
            pub_key = f.read().strip()
        
        # Get all PHP instances
        instances_cmd = [
            "gcloud", "compute", "instances", "list",
            "--filter=name:php-instance-*", 
            "--format=json"
        ]
        instances_result = subprocess.run(instances_cmd, capture_output=True, text=True, timeout=30)
        
        if instances_result.returncode == 0:
            instances = json.loads(instances_result.stdout)
            for instance in instances:
                instance_name = instance["name"]
                zone = instance["zone"].split("/")[-1]
                
                # Add SSH key to instance metadata
                subprocess.run([
                    "gcloud", "compute", "instances", "add-metadata", instance_name,
                    f"--zone={zone}",
                    f"--metadata=ssh-keys=ubuntu:{pub_key}",
                    "--quiet"
                ], capture_output=True, timeout=10)
                print(f"Added SSH key to {instance_name}", file=sys.stderr)
        
        return ssh_key_path
        
    except Exception as e:
        print(f"SSH setup error: {e}", file=sys.stderr)
        return None

def get_mig_instances():
    try:
        # First, set up SSH keys
        ssh_key_path = setup_ssh_keys()
        
        # Get instances from MIG
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
                    "ansible_user": "ubuntu",
                    "ansible_become": "yes",
                    "ansible_ssh_common_args": "-o StrictHostKeyChecking=no -o UserKnownHostsFile=/dev/null",
                    "ansible_ssh_private_key_file": ssh_key_path if ssh_key_path else "~/.ssh/ansible_key"
                }
            }
        }
        
        # Get external IPs for each instance
        for instance in instances:
            instance_name = instance["instance"].split("/")[-1]
            
            ip_cmd = [
                "gcloud", "compute", "instances", "describe", instance_name,
                "--zone=us-central1-f", 
                "--format=value(networkInterfaces[0].accessConfigs[0].natIP)"
            ]
            ip_result = subprocess.run(ip_cmd, capture_output=True, text=True, timeout=10)
            
            if ip_result.returncode == 0 and ip_result.stdout.strip():
                external_ip = ip_result.stdout.strip()
                inventory["php_servers"]["hosts"].append(external_ip)
        
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
