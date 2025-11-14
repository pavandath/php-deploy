pipeline {
    agent any
    stages {
        stage('Debug Permissions') {
            steps {
                withCredentials([file(credentialsId: 'terraform', variable: 'GCP_KEY')]) {
                    sh '''
                        gcloud auth activate-service-account --key-file=${GCP_KEY}
                        gcloud config set project siva-477505
                        
                        echo "=== Current Authentication ==="
                        gcloud auth list
                        
                        echo "=== Testing IAM Permissions ==="
                        gcloud iam service-accounts create test-debug-sa --display-name="Test Debug SA" || echo "IAM permission failed"
                        
                        echo "=== Testing Compute Permissions ==="
                        gcloud compute health-checks create http test-debug-hc || echo "Compute permission failed"
                        
                        echo "=== Current Service Account Info ==="
                        gcloud iam service-accounts describe terraform-srvc@siva-477505.iam.gserviceaccount.com
                    '''
                }
            }
        }
    }
}
