pipeline {
    agent any
    
    environment {
        GCP_KEY = credentials('terraform')
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
        
        stage('User Input') {
            steps {
                script {
                    def userInput = input(
                        message: 'Choose action:', 
                        parameters: [
                            choice(choices: ['apply', 'destroy'], description: 'Select Terraform action', name: 'ACTION')
                        ]
                    )
                    env.TF_ACTION = userInput
                }
            }
        }
        
        stage('Terraform Action') {
            steps {
                dir('php-deploy') {
                    sh '''
                        export GOOGLE_APPLICATION_CREDENTIALS=${GCP_KEY}
                        ./terraform init
                    '''
                    script {
                        if (env.TF_ACTION == 'destroy') {
                            sh './terraform destroy -auto-approve'
                        } else {
                            sh './terraform apply -auto-approve'
                        }
                    }
                }
            }
        }
    }
}
