pipeline {
    agent any

    stages {
        stage('Checkout') {
            steps {
                checkout scm
            }
        }
        
        stage('Terraform Setup') {
            steps {
                sh '/usr/bin/terraform init'
            }
        }
        
        stage('Terraform Apply') {
            steps {
                sh '/usr/bin/terraform apply -auto-approve'
            }
        }
    }
}
