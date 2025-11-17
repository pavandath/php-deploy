resource "google_compute_instance_template" "php_template_cos" {
  name_prefix  = "php-cos-template-"
  machine_type = var.machine_type

  disk {
    boot = true
    auto_delete = true
    source_image = "projects/cos-cloud/global/images/family/cos-stable"
    disk_size_gb = 10
  }

  metadata = {
    enable-oslogin = "TRUE"
    google-logging-enabled = "true"
    google-monitoring-enabled = "true"
    startup-script = <<-EOF
      #!/bin/bash
      # Authenticate Docker with GAR
      docker-credential-gcr configure-docker --registries=us-central1-docker.pkg.dev
      
      # Stop and remove existing container
      docker stop php-app || true
      docker rm php-app || true
      
      # Run container
      docker run -d --name php-app -p 80:80 --restart unless-stopped ${var.image_uri}
      
      # Setup log upload every 10 minutes
      echo "*/10 * * * * docker logs php-app --since 10m > /tmp/php-logs.txt && gsutil cp /tmp/php-logs.txt gs://${var.gcs_ansible_bucket}/php-logs/\\$(hostname)-\\$(date +\\%Y\\%m\\%d_\\%H\\%M\\%S).log" | crontab -
      
      echo "Container deployed on COS"
    EOF
  }

  service_account {
    email = google_service_account.instance_sa.email
    scopes = ["https://www.googleapis.com/auth/cloud-platform"]
  }

  network_interface {
    network = "default"
    access_config {}
  }

  tags = ["http-server"]

  lifecycle {
    create_before_destroy = true
  }
}
