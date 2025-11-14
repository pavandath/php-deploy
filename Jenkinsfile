pipeline {
    agent any

    stages {
        stage('Terraform Deploy') {
            steps {
                withCredentials([file(credentialsId: 'terraform-sa-key', variable: 'GCP_KEY')]) {
                    sh '''
                        # Clean up and clone repo
                        rm -rf php-deploy || true
                        git clone https://github.com/pavandath/php-deploy.git
                        cd php-deploy
                        
                        # Download and setup Terraform
                        wget -q https://releases.hashicorp.com/terraform/1.5.7/terraform_1.5.7_linux_amd64.zip
                        busybox unzip -o terraform_1.5.7_linux_amd64.zip
                        chmod +x terraform
                        rm terraform_1.5.7_linux_amd64.zip
                        
                        # Authenticate to GCP using the service account key
                        gcloud auth activate-service-account --key-file=${GCP_KEY}
                        gcloud config set project siva-477505
                        
                        # Verify authentication
                        echo "=== Current Authentication ==="
                        gcloud auth list
                        
                        # Run Terraform
                        ./terraform init
                        ./terraform apply -auto-approve
                    '''
                }
            }
        }
    }
}
