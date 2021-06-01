pipeline {
  agent any
  stages {
    stage('START') {
      parallel {
        stage('START') {
          steps {
            echo 'Docker hub login...'
          }
        }

        stage('HUB LOGIN') {
          steps {
            timestamps() {
              echo 'Time:'
            }

          }
        }

      }
    }

    stage('BUILD') {
      steps {
        sh 'make build'
      }
    }

    stage('BENCH') {
      steps {
        sh 'make bench'
      }
    }

  }
}