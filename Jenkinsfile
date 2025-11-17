pipeline {
    agent any
    
    environment {
        GCP_KEY = credentials('terraform')
        GOOGLE_APPLICATION_CREDENTIALS = "${GCP_KEY}"
    }

    stages {
        stage('Terraform install') {
            steps {
                sh '''
                    rm -rf php-deploy
                    git clone https://github.com/pavandath/php-deploy.git || true
                '''
                dir('php-deploy'){  
                    sh '''
                        wget -q https://releases.hashicorp.com/terraform/1.5.7/terraform_1.5.7_linux_amd64.zip
                        busybox unzip -o terraform_1.5.7_linux_amd64.zip
                        chmod +x terraform
                        rm terraform_1.5.7_linux_amd64.zip
                    '''
                }
            }
        }
        
        stage('terraform apply') {
            steps {
                dir('php-deploy') {
                    sh '''
                        ./terraform init
                        ./terraform apply -auto-approve -lock=false
                    '''
                }
            }
        }
        
        stage('Destroy Confirmation') {
            steps {
                input(
                    message: 'Do you want to destroy the infrastructure?', 
                    ok: 'Proceed',
                    parameters: [
                        choice(choices: ['no', 'yes'], description: 'Select action', name: 'DESTROY')
                    ]
                )
            }
        }
        
        stage('Terraform Destroy') {
            when {
                expression { 
                    env.DESTROY == 'yes'
                }
            }
            steps {
                dir('php-deploy') {
                    sh '''
                        ./terraform destroy -auto-approve -lock=false
                    '''
                }
            }
        }
    }
    
    post {
        always {
            echo "Pipeline completed successfully"
        }
    }
}
