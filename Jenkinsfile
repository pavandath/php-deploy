pipeline {
    agent any
    
    environment {
        GCP_KEY = credentials('terraform')
        GOOGLE_APPLICATION_CREDENTIALS = "${GCP_KEY}"
        GAR_LOCATION = "us-central1"
        GAR_REPO = "siva-477505/php-app"
    }

    stages {
        stage('Checkout Code') {
            steps {
                git branch: 'main', url: 'https://github.com/pavandath/php-deploy.git'
            }
        }
        
        stage('Build and Push Docker') {
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
        
        stage('Deploy with Terraform') {
            steps {
                sh '''
                    wget -q https://releases.hashicorp.com/terraform/1.5.7/terraform_1.5.7_linux_amd64.zip
                    busybox unzip -o terraform_1.5.7_linux_amd64.zip
                    chmod +x terraform
                    rm terraform_1.5.7_linux_amd64.zip
                    
                    ./terraform init
                    ./terraform apply -auto-approve
                '''
            }
        }
        
        stage('Destroy Infrastructure') {
            steps {
                input(
                    message: 'Do you want to destroy everything?', 
                    ok: 'Destroy',
                    parameters: [
                        choice(choices: ['no', 'yes'], description: 'Confirm destruction', name: 'DESTROY')
                    ]
                )
                script {
                    if (params.DESTROY == 'yes') {
                        sh './terraform destroy -auto-approve'
                    }
                }
            }
        }
    }
    
    post {
        always {
            echo "Pipeline completed"
        }
    }
}
