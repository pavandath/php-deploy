pipeline {
    agent any

    stages {
        stage('Terraform Deploy Infrastructure') {
            steps {
                withCredentials([file(credentialsId: 'terraform', variable: 'GCP_KEY')]) {
                    sh '''
                        rm -rf php-deploy
                        git clone https://github.com/pavandath/php-deploy.git
                        cd php-deploy
                        
                        # Clear any cached state
                        rm -rf .terraform .terraform.lock.hcl
                        
                        # Use environment variable for Terraform
                        export GOOGLE_APPLICATION_CREDENTIALS=${GCP_KEY}
                        
                        terraform init
                        terraform apply -auto-approve
                    '''
                }
            }
        }

        stage('Get Ansible Master IP') {
            steps {
                withCredentials([file(credentialsId: 'terraform', variable: 'GCP_KEY')]) {
                    sh '''
                        cd php-deploy
                        export GOOGLE_APPLICATION_CREDENTIALS=${GCP_KEY}
                        
                        # Get the Ansible master IP from Terraform output
                        ANSIBLE_MASTER_IP=$(terraform output -raw ansible_master_ip)
                        echo "Ansible Master IP: $ANSIBLE_MASTER_IP"
                        
                        # Wait for SSH to be available
                        echo "Waiting for SSH to be ready..."
                        until nc -z $ANSIBLE_MASTER_IP 22; do
                            sleep 10
                            echo "Still waiting for SSH..."
                        done
                        echo "SSH is ready!"
                    '''
                }
            }
        }

        stage('Deploy Application with Ansible') {
            steps {
                sshagent(['ansible-master-ssh-key']) {
                    withCredentials([file(credentialsId: 'terraform', variable: 'GCP_KEY')]) {
                        sh '''
                            cd php-deploy
                            export GOOGLE_APPLICATION_CREDENTIALS=${GCP_KEY}
                            ANSIBLE_MASTER_IP=$(terraform output -raw ansible_master_ip)
                            
                            echo "Deploying to Ansible Master: $ANSIBLE_MASTER_IP"
                            
                            # Copy Ansible files to the Ansible master
                            scp -o StrictHostKeyChecking=no -r ansible/ ubuntu@${ANSIBLE_MASTER_IP}:~/
                            
                            # Execute Ansible playbook (EXACTLY as you specified)
                            ssh -o StrictHostKeyChecking=no ubuntu@${ANSIBLE_MASTER_IP} '
                                cd ansible
                                chmod +x inventory-gcp.py
                                
                                # Install Ansible if not present
                                sudo apt-get update
                                sudo apt-get install -y ansible python3-pip
                                pip3 install google-auth requests
                                
                                # Run the Ansible playbook
                                ansible-playbook -i inventory-gcp.py deploy-php.yml
                            '
                        '''
                    }
                }
            }
        }
    }

    post {
        always {
            echo "Pipeline execution completed"
        }
        success {
            echo "✅ Infrastructure and application deployed successfully!"
        }
        failure {
            echo "❌ Pipeline failed"
        }
    }
}
