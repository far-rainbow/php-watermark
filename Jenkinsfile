pipeline {
  agent {
    node {
      label 'test-1'
    }

  }
  stages {
    stage('START') {
      steps {
        echo 'TEST MSG'
      }
    }

    stage('BUILD & BENCH') {
      steps {
        sh 'make php-watermark'
      }
    }

  }
}