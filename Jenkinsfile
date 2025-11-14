pipeline {
    agent any

    stages {
        stage('Terraform Deploy') {
            steps {
                sh '''
                    Clone repo
                    git clone https://github.com/pavandath/php-deploy.git
                    cd php-deploy
                    ./terraform init
                    ./terraform apply -auto-approve
                '''
            }
        }
    }
}
