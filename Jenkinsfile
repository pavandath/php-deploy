pipeline {
    agent any
    stages {
        stage('Terraform Deploy') {
            steps {
                sh '''
                    rm -rf php-deploy
                    git clone https://github.com/pavandath/php-deploy.git
                    cd php-deploy
                    
                    # Download Terraform
                    wget -q https://releases.hashicorp.com/terraform/1.5.7/terraform_1.5.7_linux_amd64.zip
                    busybox unzip -o terraform_1.5.7_linux_amd64.zip
                    chmod +x terraform
                    rm terraform_1.5.7_linux_amd64.zip
                    
                    # Switch to your personal account
                    gcloud auth activate-service-account pavandathb@gmail.com --brief || echo "Account already active"
                    gcloud config set project siva-477505
                    
                    # Run Terraform
                    ./terraform init
                    ./terraform apply -auto-approve
                '''
            }
        }
    }
}
