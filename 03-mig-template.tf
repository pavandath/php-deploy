resource "google_compute_instance_template" "php_template_ubuntu" {
  name_prefix  = "php-ub-template-"
  machine_type = var.machine_type

  disk {
    boot = true
    auto_delete = true
    source_image = "projects/ubuntu-os-cloud/global/images/family/ubuntu-2204-lts"
    disk_size_gb = 10
  }

  metadata = {
    enable-oslogin = "TRUE"
    startup-script = <<-EOF
      #!/bin/bash
      # Install Docker
      apt update -y
      apt install docker.io -y
      systemctl enable docker
      systemctl start docker
      

      gcloud auth configure-docker us-central1-docker.pkg.dev

      docker stop php-app || true
      docker rm php-app || true
      

      docker run -d --name php-app -p 80:80 --restart unless-stopped us-central1-docker.pkg.dev/siva-477505/php-app/php-app:latest
      
     
      sleep 10
      
      # Generate FIRST LOG immediately for verification
      docker logs php-app > /tmp/first-log.txt
      gsutil cp /tmp/first-log.txt gs://pavan-gcs/php-logs/$(hostname)-first.log
      
      # log upload every 10 minutes
      echo "*/10 * * * * docker logs php-app --since 10m > /tmp/php-logs.txt && gsutil cp /tmp/php-logs.txt gs://pavan-gcs/php-logs/$(hostname)-\\$(date +\\%Y\\%m\\%d_\\%H\\%M\\%S).log" | crontab -
      
      echo "Container running - first log uploaded for verification"
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
