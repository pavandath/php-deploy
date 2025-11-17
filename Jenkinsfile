pipeline {
    agent any
    
    environment {
        GCP_KEY = credentials('terraform')
        GOOGLE_APPLICATION_CREDENTIALS = "${GCP_KEY}"
        GAR_LOCATION = "us-central1"
        GAR_REPO = "siva-477505/php-app"
    }

    triggers {
        githubPush()
    }

    stages {
        stage('Check for Docker Changes') {
            steps {
                script {
                    def changes = sh(
                        script: "git diff --name-only HEAD~1 HEAD | grep -E '(Dockerfile|app/)' || true",
                        returnStdout: true
                    ).trim()
                    
                    if (changes) {
                        env.BUILD_AND_DEPLOY = "true"
                        echo "Docker changes detected: ${changes}. Will build and deploy."
                    } else {
                        env.BUILD_AND_DEPLOY = "false"
                        currentBuild.result = 'SUCCESS'
                        error('No Docker changes - skipping build and deploy')
                    }
                }
            }
        }
        
        stage('Build and Push Docker') {
            when {
                expression { env.BUILD_AND_DEPLOY == "true" }
            }
            steps {
                dir('app') {
                    sh '''
                        docker build -t ${GAR_LOCATION}-docker.pkg.dev/${GAR_REPO}/php-app:latest .
                        gcloud auth configure-docker ${GAR_LOCATION}-docker.pkg.dev --quiet
                        docker push ${GAR_LOCATION}-docker.pkg.dev/${GAR_REPO}/php-app:latest
                    '''
                }
            }
        }
        
        stage('Rollout to MIG') {
            when {
                expression { env.BUILD_AND_DEPLOY == "true" }
            }
            steps {
                sh '''
                    # Update MIG instances with new container
                    gcloud compute instance-groups managed rolling-action start-update php-mig \
                        --region=us-central1 \
                        --max-unavailable=0 || true
                '''
            }
        }
    }
    
    post {
        always {
            echo "Pipeline completed"
        }
    }
}
