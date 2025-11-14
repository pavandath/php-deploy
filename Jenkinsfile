pipeline {
    agent any
    stages {
        stage('Terraform Deploy') {
            steps {
                sh '''
                    rm -rf php-deploy
                    git clone https://github.com/pavandath/php-deploy.git
                    cd php-deploy
                    
                    # Remove any service account authentication
                    gcloud config unset auth/impersonate_service_account 2>/dev/null || true
                    
                    # Use your personal account (already logged in)
                    gcloud auth list
                    gcloud config set project siva-477505
                    
                    terraform init
                    terraform apply -auto-approve
                '''
            }
        }
    }
}
