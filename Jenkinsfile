pipeline {
    agent any
    
    environment {
        GCP_KEY = credentials('terraform')
    }

    stages {
        stage('Terraform Deploy') {
            steps {
                sh '''
                    # Clone only if doesn't exist, ignore errors
                    git clone https://github.com/pavandath/php-deploy.git || true
                    cd php-deploy
                    
                    # Download Terraform only if not exists
                    if [ ! -f "terraform" ]; then
                        wget -q https://releases.hashicorp.com/terraform/1.5.7/terraform_1.5.7_linux_amd64.zip
                        busybox unzip -o terraform_1.5.7_linux_amd64.zip
                        chmod +x terraform
                        rm terraform_1.5.7_linux_amd64.zip
                    fi
                    
                    export GOOGLE_APPLICATION_CREDENTIALS=${GCP_KEY}
                    ./terraform init
                    ./terraform apply -auto-approve
                '''
            }
        }

        stage('Ansible Deploy') {
            steps {
                sshagent(['ansible-master-ssh-key']) {
                    sh '''
                        cd php-deploy
                        export GOOGLE_APPLICATION_CREDENTIALS=${GCP_KEY}
                        ANSIBLE_MASTER_IP=$(gcloud compute instances list --filter="name:ansible-master" --format="value(EXTERNAL_IP)" --project=siva-477505)
                        
                        ssh -o StrictHostKeyChecking=no ubuntu@${ANSIBLE_MASTER_IP} '
                            cd php-deploy/ansible
                            chmod +x inventory-gcp.py
                            ansible-playbook -i inventory-gcp.py deploy-php.yml
                        '
                    '''
                }
            }
        }
    }
}
