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
        
        stage('Clean and Setup Terraform') {
            steps {
                sh '''
                    rm -rf .terraform terraform.tfstate* .terraform.lock.hcl
                    wget -q https://releases.hashicorp.com/terraform/1.5.7/terraform_1.5.7_linux_amd64.zip
                    busybox unzip -o terraform_1.5.7_linux_amd64.zip
                    chmod +x terraform
                    rm terraform_1.5.7_linux_amd64.zip
                '''
            }
        }
        
        stage('Deploy with Terraform') {
            steps {
                sh '''
                    ./terraform init
                    ./terraform apply -replace="google_compute_region_instance_group_manager.php_mig" -auto-approve || true
                '''
            }
        }
    }
    
    post {
        always {
            echo "Pipeline completed"
        }
    }
}
