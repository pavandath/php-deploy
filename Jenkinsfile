pipeline {
    agent any

    stages {
        stage('Clone and Deploy') {
            steps {
                sh 'rm -rf php-deploy && git clone https://github.com/pavandath/php-deploy.git'
                dir('php-deploy') {
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
        }
    }
}
