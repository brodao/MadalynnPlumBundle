<?php

/*
 * This file is part of the Madalynn package.
 *
 * (c) 2010-2011 Julien Brochet <mewt@madalynn.eu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Madalynn\Bundle\PlumBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class DeployCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('plum:deploy')
            ->setDescription('Deploys a project to another server')
            ->addArgument('server', InputArgument::REQUIRED, 'The server name')
            ->addArgument('deployer', InputArgument::OPTIONAL, 'The deployer name', 'rsync')
            ->setHelp(<<<EOF
The <info>project:deploy</info> command deploys a project on a server:

  <info>php app/console project:deploy production</info>

The server must be configured in <comment>app/config/config_dev.yml</comment>:

    madalynn_plum:
        deployers:
            - Plum\Deployer\RsyncDeployer
        servers:
            production:
                host: www.mywebsite.com
                port: 22
                user: julien
                dir: /var/www/sfblog/

To automate the deployment, the task uses rsync over SSH.
You must configure SSH access with a key or configure the password
in <comment>app/config/config_dev.yml</comment>.
EOF
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $server   = $input->getArgument('server');
        $deployer = $input->getArgument('deployer');
        $plum     = $this->getContainer()->get('madalynn.plum');
        $options  = $this->getContainer()->getParameter('plum.server.' . $server . '.options');

        if (isset($options['dry_run']) && $options['dry_run']) {
            $dryrun = '<comment>(dry run mode)</comment>';
        } else {
            $dryrun = '';
        }

        $output->writeln(sprintf('Starting rsync to <info>%s</info> %s', $server, $dryrun));

        // Let's go!
        $plum->deploy($server, $deployer, $options);

        $output->writeln(sprintf('Successfully rsync to <info>%s</info>', $server));
    }
}

