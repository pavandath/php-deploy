pipeline {
    agent any

    environment {
        # This will make Terraform skip creating new service accounts
        TF_VAR_use_existing_service_account = "true"
        TF_VAR_existing_service_account = "terraform-srvc@siva-477505.iam.gserviceaccount.com"
    }

    stages {
        stage('Checkout') {
            steps {
                checkout scm
            }
        }
        
        stage('Setup Terraform') {
            steps {
                sh '''
                    wget -q https://releases.hashicorp.com/terraform/1.5.7/terraform_1.5.7_linux_amd64.zip
                    busybox unzip -o terraform_1.5.7_linux_amd64.zip
                    chmod +x terraform
                    rm terraform_1.5.7_linux_amd64.zip
                '''
            }
        }
        
        stage('Terraform Apply') {
            steps {
                sh '''
                    ./terraform init
                    ./terraform apply -auto-approve
                '''
            }
        }
    }
}
