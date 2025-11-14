pipeline {
    agent any
    stages {
        stage('Checkout') {
            steps {
                checkout scm
            }
        }
        stage('Download Terraform') {
            steps {
                sh '''
                    # Try busybox unzip first
                    wget -q https://releases.hashicorp.com/terraform/1.5.7/terraform_1.5.7_linux_amd64.zip
                    
                    # Try different unzip methods
                    if command -v busybox > /dev/null; then
                        busybox unzip -o terraform_1.5.7_linux_amd64.zip
                    else
                        # Use Python to extract zip
                        python3 -c "import zipfile; zipfile.ZipFile('terraform_1.5.7_linux_amd64.zip').extractall()"
                    fi
                    
                    chmod +x terraform
                    ./terraform --version
                    rm terraform_1.5.7_linux_amd64.zip
                '''
            }
        }
        stage('Terraform Setup') {
            steps {
                sh './terraform init -input=false'
            }
        }
        stage('Terraform Apply') {
            steps {
                sh './terraform apply -auto-approve'
            }
        }
    }
}
