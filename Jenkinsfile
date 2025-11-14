pipeline {
    agent any
    stages {
        stage('Terraform Deploy') {
            steps {
                withCredentials([file(credentialsId: 'terraform', variable: 'GCP_KEY')]) {
                    sh '''
                        rm -rf php-deploy
                        git clone https://github.com/pavandath/php-deploy.git
                        cd php-deploy
                        
                        wget -q https://releases.hashicorp.com/terraform/1.5.7/terraform_1.5.7_linux_amd64.zip
                        busybox unzip -o terraform_1.5.7_linux_amd64.zip
                        chmod +x terraform
                        rm terraform_1.5.7_linux_amd64.zip
                        
                        gcloud auth activate-service-account --key-file=${GCP_KEY}
                        gcloud config set project siva-477505
                        
                        ./terraform init
                        
                        # Import the existing service account into Terraform state
                        ./terraform import google_service_account.instance_sa "projects/siva-477505/serviceAccounts/php-instance@siva-477505.iam.gserviceaccount.com"
                        
                        ./terraform apply -auto-approve
                    '''
                }
            }
        }
    }
}
