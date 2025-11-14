pipeline {
    agent any
    
    environment {
        GCP_KEY = credentials('terraform')
    }

    stages {
        stage('Terraform Deploy') {
            steps {
                sh '''
                    rm -rf php-deploy
                    git clone https://github.com/pavandath/php-deploy.git || true
                    cd php-deploy
                    
                    wget -q https://releases.hashicorp.com/terraform/1.5.7/terraform_1.5.7_linux_amd64.zip
                    busybox unzip -o terraform_1.5.7_linux_amd64.zip
                    chmod +x terraform
                    rm terraform_1.5.7_linux_amd64.zip
                    
                    export GOOGLE_APPLICATION_CREDENTIALS=${GCP_KEY}
                    ./terraform init
                    ./terraform apply -auto-approve
                '''
            }
        }
        
       stage('Ansible Deploy') {
    steps {
        sh '''
            cd php-deploy
            export GOOGLE_APPLICATION_CREDENTIALS=${GCP_KEY}
            
           
                su ansible -c "
                cd /home/sivapk188/workspace/php-deploy/ansible
                chmod +x inventory-gcp.py
                export GOOGLE_APPLICATION_CREDENTIALS=${GCP_KEY}
                ansible-playbook -i inventory-gcp.py deploy-php.yml
            " <<< "1234"

           
        '''
    }
}

    }
}
