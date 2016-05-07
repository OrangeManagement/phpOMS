<?php
/**
 * Orange Management
 *
 * PHP Version 7.0
 *
 * @category   TBD
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @copyright  2013 Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
namespace phpOMS\Utils\Git;

use phpOMS\System\File\PathException;
use phpOMS\Validation\Validator;

/**
 * Repository class
 *
 * @category   Framework
 * @package    phpOMS\Asset
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class Repository
{
    /**
     * Repository path.
     *
     * @var string
     * @since 1.0.0
     */
    private $path = '';

    /**
     * Bare repository.
     *
     * @var bool
     * @since 1.0.0
     */
    private $bare = false;

    /**
     * Env variables.
     *
     * @var array
     * @since 1.0.0
     */
    private $envOptions = [];

    /**
     * Current branch.
     *
     * @var Branch
     * @since 1.0.0
     */
    private $branch = null;

    /**
     * Constructor
     *
     * @param string $path Repository path
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function __construct(string $path)
    {
        $this->branch = $this->getActiveBranch();
        $this->setPath($path);
    }

    /**
     * Create repository
     *
     * @param string $source Create repository from source (optional, can be remote)
     * @param bool   $bare   Bare repository
     *
     * @throws \Exception
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function create(string $source = null, bool $bare = false)
    {
        if (!is_dir($this->path) || file_exists($this->path . '/.git')) {
            throw new \Exception('Already repository');
        }

        if (isset($source)) {
            $this->clone($source);
        } else {
            $this->init($bare);
        }
    }

    /**
     * Set repository path.
     *
     * @param string $path Path to repository
     *
     * @throws PathException
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    private function setPath(string $path)
    {
        if (!is_dir($path)) {
            throw new PathException($path);
        }

        $this->path = realpath($path);

        if ($this->path === false || !Validator::startsWith($this->path, ROOT_PATH)) {
            throw new PathException($path);
        }

        if (file_exists($this->path . '/.git') && is_dir($this->path . '/.git')) {
            $this->bare = false;
        } elseif (is_file($this->path . '/config')) { // Is this a bare repo?
            $parseIni = parse_ini_file($this->path . '/config');

            if ($parseIni['bare']) {
                $this->bare = true;
            }
        }
    }

    /**
     * Run git command.
     *
     * @param string $cmd Command to run
     *
     * @return array
     *
     * @throws \Exception
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    private function run(string $cmd) : array
    {
        $cmd   = Git::getBin() . ' ' . $cmd;
        $pipes = [];
        $desc  = [
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w'],
        ];

        if (count($_ENV) === 0) {
            $env = null;
            foreach ($this->envOptions as $key => $value) {
                putenv(sprintf("%s=%s", $key, $value));
            }
        } else {
            $env = array_merge($_ENV, $this->envOptions);
        }

        $resource = proc_open($cmd, $desc, $pipes, $this->path, $env);
        $stdout   = stream_get_contents($pipes[1]);
        $stderr   = stream_get_contents($pipes[2]);

        foreach ($pipes as $pipe) {
            fclose($pipe);
        }

        $status = trim(proc_close($resource));

        if ($status) {
            throw new \Exception($stderr);
        }

        return $this->parseLines($stdout);
    }

    /**
     * Parse lines.
     *
     * @param string $lines Result of git command
     *
     * @return array
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    private function parseLines(string $lines) : array
    {
        $lines     = preg_replace('/!\\t+/', '|', $lines);
        $lines     = preg_replace('!\s+!', ' ', $lines);
        $lineArray = preg_split('/\\r\\n|\\r|\\n/', $lines);

        foreach ($lineArray as $key => $line) {
            $lineArray[$key] = trim($line, ' |');

            if (empty($line)) {
                unset($lineArray[$key]);
            } else {
                $lineArray[$key] = $line;
            }
        }

        return $lineArray;

    }

    /**
     * Get directory path.
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getDirectoryPath() : string
    {
        return $this->bare ? $this->path : $this->path . '/.git';
    }

    /**
     * Get status.
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function status() : string
    {
        return implode("\n", $this->run('status'));
    }

    /**
     * Files to add to commit.
     *
     * @param string|array $files Files to commit
     *
     * @return string
     *
     * @throws \Exception
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function add($files = '*') : string
    {
        if (is_array($files)) {
            $files = '"' . implode('" "', $files) . '"';
        } elseif (!is_string($files)) {
            throw new \Exception('Wrong type');
        }

        return implode("\n", $this->run('add ' . $files . ' -v'));
    }

    public function rm($files = '*', bool $cached = false) : string
    {
        if (is_array($files)) {
            $files = '"' . implode('" "', $files) . '"';
        } elseif (!is_string($files)) {
            throw new \Exception('Wrong type');
        }

        return $this->run('rm ' . ($cached ? '--cached ' : '') . $files);
    }

    /**
     * Commit files.
     *
     * @param Commit $commit Commit to commit
     * @param bool   $all    Commit all
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function commit(Commit $commit, $all = true) : string
    {
        return implode("\n", $this->run('commit ' . ($all ? '-av' : '-v') . ' -m ' . escapeshellarg($commit->getMessage())));
    }

    public function cloneTo(string $target) : string
    {
        if (!is_dir($target)) {
            throw new \Exception('Not a directory');
        }

        return $this->run('clone --local ' . $this->path . ' ' . $target);
    }

    public function cloneFrom(string $source) : string
    {
        if (!is_dir($source)) {
            throw new \Exception('Not a directory');
        }

        // todo: is valid git repository?

        return $this->run('clone --local ' . $source . ' ' . $this->path);
    }

    public function cloneRemote(string $source) : string
    {
        // todo: is valid remote git repository?

        return $this->run('clone ' . $source . ' ' . $this->path);
    }

    /**
     * Clean.
     *
     * @param bool $dirs  Directories?
     * @param bool $force Force?
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function clean(bool $dirs = false, bool $force = false) : string
    {
        return implode("\n", $this->run('clean' . ($force ? ' -f' : '') . ($dirs ? ' -d' : '')));
    }

    /**
     * Create local branch.
     *
     * @param Branch $branch  Branch
     * @param bool $force Force?
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function createBranch(Branch $branch, bool $force = false) : string
    {
        return implode("\n", $this->run('branch ' . ($force ? '-D' : '-d') . ' ' . $branch->getName()));
    }

    public function getBranches() : array
    {
        $branches = $this->run('branch');

        foreach ($branches as $key => &$branch) {
            $branch = trim($branch);

            if ($branch === '') {
                unset($branches[$key]);
            }
        }

        return $branches;
    }

    public function getBranchesRemote() : array
    {
        $branches = $this->run('branch -r');

        foreach ($branches as $key => &$branch) {
            $branch = trim($branch);

            if ($branch === '' || strpos($branch, 'HEAD -> ') !== false) {
                unset($branches[$key]);
            }
        }

        return $branches;
    }

    /**
     * Get active Branch.
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getActiveBranch() : string
    {
        if (!isset($branch)) {
            $branches = $this->getBranches();
            $active   = preg_grep('/^\*/', $branches);
            reset($active);

            $this->branch = new Branch(current($active));
        }

        return $this->branch;
    }

    /**
     * Checkout.
     *
     * @param Branch $branch Branch to checkout
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function checkout(Branch $branch) : string
    {
        $result       = implode("\n", $this->run('checkout ' . $branch->getName()));
        $this->branch = null;

        return $result;
    }

    /**
     * Merge with branch.
     *
     * @param Branch $branch Branch to merge from
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function merge(Branch $branch) : string
    {
        return implode("\n", $this->run('merge ' . $branch->getName() . ' --no-ff'));
    }

    /**
     * Fetch.
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function fetch() : string
    {
        return implode("\n", $this->run('fetch'));
    }

    /**
     * Create tag.
     *
     * @param Tag $tag Tag to create
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function createTag(Tag $tag) : string
    {
        return implode("\n", $this->run('tag -a ' . $tag->getName() . ' -m ' . escapeshellarg($tag->getMessage())));
    }

    /**
     * Get all tags.
     *
     * @param string $pattern Tag pattern
     *
     * @return Tag[]
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getTags(string $pattern = '') : array
    {
        $pattern = empty($pattern) ? ' -l ' . $pattern : '';
        $lines   = $this->run('tag' . $pattern);
        $tags    = [];

        foreach ($lines as $key => $tag) {
            $tags[$tag] = new Tag($tag);
        }

        return $tags;
    }

    /**
     * Push.
     *
     * @param string $remote Remote repository
     * @param Branch $branch Branch to pull
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function push(string $remote, Branch $branch) : string
    {
        $remote = escapeshellarg($remote);

        return implode("\n", $this->run('push --tags ' . $remote . ' ' . $branch->getName()));
    }

    /**
     * Pull.
     *
     * @param string $remote Remote repository
     * @param Branch $branch Branch to pull
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function pull(string $remote, Branch $branch) : string
    {
        $remote = escapeshellarg($remote);

        return implode("\n", $this->run('pull ' . $remote . ' ' . $branch->getName()));
    }

    /**
     * Set repository description.
     *
     * @param string $description Repository description
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setDescription(string $description)
    {
        file_put_contents($this->getDirectoryPath(), $description);
    }

    /**
     * Get repository description.
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getDescription() : string
    {
        return file_get_contents($this->getDirectoryPath() . '/description');
    }

    /**
     * Set environment value.
     *
     * @param string $key   Key
     * @param string $value Value
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setEnv(string $key, string $value)
    {
        $this->envOptions[$key] = $value;
    }

    /**
     * Get commit by id.
     *
     * @param string $commit Commit id
     *
     * @return Commit
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getCommit(string $commit) : Commit
    {
        $commit = escapeshellarg($commit);
        $lines  = $this->run('log show --name-only ' . $commit);
        $count  = count($lines);

        preg_match('/[0-9ABCDEFabcdef]{40}/', $lines[0], $matches);
        $author = explode(':', $lines[1]);
        $author = explode('<', trim($author[1]));
        $date   = explode(':', $lines[2]);

        $commit = new Commit($matches[0]);
        $commit->setAuthor(new Author(trim($author[0]), rtrim($author[1], '>')));
        $commit->setDate(new \DateTime(trim($date[1])));
        $commit->setMessage($lines[3]);
        $commit->setTag(new Tag());
        $commit->setRepository($this);
        $commit->setBranch($this->branch);

        for ($i = 4; $i < $count; $i++) {
            $commit->addFile($lines[$i]);
        }

        return $commit;
    }

    /**
     * Count commits.
     *
     * @param \DateTime $start Start date
     * @param \DateTime $end   End date
     *
     * @return array
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getCommitsCount(\DateTime $start = null, \DateTime $end = null) : array
    {
        if (!isset($start)) {
            $start = new \DateTime('1970-12-31');
        }

        if (!isset($end)) {
            $end = new \DateTime('now');
        }

        $lines   = $this->run('shortlog -s -n --since="' . $start->format('Y-m-d') . '" --before="' . $end->format('Y-m-d') . '" --all');
        $commits = [];

        foreach ($lines as $line) {
            $count              = explode('|', $line);
            $commits[$count[1]] = $count[0];
        }

        return $commits;
    }

    /**
     * Get remote.
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getRemote() : string
    {
        return $this->run('config --get remote.origin.url');
    }

    /**
     * Get commits by author.
     *
     * @param \DateTime $start  Commits from
     * @param \DateTime $end    Commits to
     * @param Author    $author Commits by author
     *
     * @return Commit[]
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getCommitsBy(\DateTime $start = null, \DateTime $end = null, Author $author = null) : array
    {
        if (!isset($start)) {
            $start = new \DateTime('1970-12-31');
        }

        if (!isset($end)) {
            $end = new \DateTime('now');
        }

        if (!isset($author)) {
            $author = '';
        } else {
            $author = ' --author="' . $author->getName() . '"';
        }

        $lines   = $this->run('git log --before="' . $end->format('Y-m-d') . '" --after="' . $start->format('Y-m-d') . '"' . $author . ' --reverse --date=short');
        $count   = count($lines);
        $commits = [];

        for ($i = 0; $i < $count; $i++) {
            $match = preg_match('/[0-9ABCDEFabcdef]{40}/', $lines[$i], $matches);

            if ($match !== false && $match !== 0) {
                $commit                    = $this->getCommit($matches[0]);
                $commits[$commit->getId()] = $commit;
            }
        }

        return $commits;
    }

    /**
     * Get newest commit.
     *
     * @return Commit
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getNewest() : Commit
    {
        $lines = $this->run('log --name-status HEAD^..HEAD');

        preg_match('[0-9ABCDEFabcdef]{40}', $lines[0], $matches);

        return $this->getCommit($matches[0]);
    }
}
