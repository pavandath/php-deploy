pipeline {
    agent any
    
    environment {
        GCP_KEY = credentials('terraform')
        GOOGLE_APPLICATION_CREDENTIALS = "${GCP_KEY}"
        GAR_LOCATION = "us-central1"
        GAR_REPO = "siva-477505/php-app"
    }

    stages {
        stage('Checkout Terraform and App Code') {
            steps {
                git branch: 'main', url: 'https://github.com/pavandath/php-deploy.git'
            }
        }
        
        stage('Build and Push to GAR') {
            steps {
                dir('app') {
                    sh '''
                        docker build -t ${GAR_LOCATION}-docker.pkg.dev/${GAR_REPO}/php-app:latest .
                        gcloud auth configure-docker ${GAR_LOCATION}-docker.pkg.dev --quiet
                        docker push ${GAR_LOCATION}-docker.pkg.dev/${GAR_REPO}/php-app:latest
                    '''
                }
            }
        }
        
        stage('Terraform Install') {
            steps {
                sh '''
                    wget -q https://releases.hashicorp.com/terraform/1.5.7/terraform_1.5.7_linux_amd64.zip
                    busybox unzip -o terraform_1.5.7_linux_amd64.zip
                    chmod +x terraform
                    rm terraform_1.5.7_linux_amd64.zip
                '''
            }
        }
        
        stage('Terraform Deploy') {
            steps {
                sh '''
                    ./terraform init  -reconfigure
                    ./terraform apply -auto-approve 
                '''
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
                sh '''
                    ./terraform destroy -auto-approve
                '''
            }
        }
    }
    
    post {
        always {
            echo "Pipeline completed successfully"
        }
    }
}
